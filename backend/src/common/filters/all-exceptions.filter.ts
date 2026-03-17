import {
  ExceptionFilter,
  Catch,
  ArgumentsHost,
  HttpException,
  HttpStatus,
  Logger,
} from "@nestjs/common";
import { Request, Response } from "express";
import { Prisma } from "@prisma/client";

/**
 * Global exception filter.
 * Converts all exceptions to a standardized JSON error response.
 * Handles HTTP exceptions, Prisma errors, and unexpected errors.
 */
@Catch()
export class AllExceptionsFilter implements ExceptionFilter {
  private readonly logger = new Logger(AllExceptionsFilter.name);

  catch(exception: unknown, host: ArgumentsHost) {
    const ctx = host.switchToHttp();
    const response = ctx.getResponse<Response>();
    const request = ctx.getRequest<Request>();

    let statusCode = HttpStatus.INTERNAL_SERVER_ERROR;
    let message: string | string[] = "Internal server error";
    let error = "Internal Server Error";
    let details: unknown = undefined;
    const requestId = String(request.headers["x-request-id"] || "n/a");

    if (exception instanceof HttpException) {
      statusCode = exception.getStatus();
      const exceptionResponse = exception.getResponse();
      if (typeof exceptionResponse === "string") {
        message = exceptionResponse;
        error = exception.name;
      } else if (typeof exceptionResponse === "object") {
        const obj = exceptionResponse as Record<string, unknown>;
        message = (obj.message as string | string[]) || message;
        error = (obj.error as string) || exception.name;
        details = obj.details;
      }
    } else if (exception instanceof Prisma.PrismaClientKnownRequestError) {
      // Handle known Prisma errors
      if (exception.code === "P2002") {
        statusCode = HttpStatus.CONFLICT;
        message = "A record with this value already exists";
        error = "Conflict";
      } else if (exception.code === "P2025") {
        statusCode = HttpStatus.NOT_FOUND;
        message = "Record not found";
        error = "Not Found";
      } else {
        // Don't expose raw Prisma error details in production
        this.logger.error(`Prisma error: ${exception.code}`, exception.message);
        message = "Database operation failed";
      }
    } else if (exception instanceof Error) {
      // Unexpected errors — log but don't expose internals
      this.logger.error(
        `Unhandled exception: ${exception.message}`,
        exception.stack,
      );
      message = "An unexpected error occurred";
    }

    response.status(statusCode).json({
      statusCode,
      error,
      message,
      requestId,
      ...(details ? { details } : {}),
      timestamp: new Date().toISOString(),
      path: request.url,
    });
  }
}
