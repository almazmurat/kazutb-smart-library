import {
  Injectable,
  NestInterceptor,
  ExecutionContext,
  CallHandler,
  Logger,
} from "@nestjs/common";
import { Observable } from "rxjs";
import { tap } from "rxjs/operators";
import { Request, Response } from "express";

/**
 * Global logging interceptor.
 * Logs every incoming request with method, path, status code, and duration.
 * Does NOT log request bodies to avoid leaking sensitive data (e.g., passwords).
 */
@Injectable()
export class LoggingInterceptor implements NestInterceptor {
  private readonly logger = new Logger("HTTP");

  intercept(context: ExecutionContext, next: CallHandler): Observable<unknown> {
    const ctx = context.switchToHttp();
    const request = ctx.getRequest<Request>();
    const response = ctx.getResponse<Response>();

    const { method, url } = request;
    const requestId = String(request.headers["x-request-id"] || "n/a");
    const userId =
      (request as Request & { user?: { id?: string } }).user?.id || "anonymous";
    const ip = request.ip;
    const startTime = Date.now();

    return next.handle().pipe(
      tap(() => {
        const duration = Date.now() - startTime;
        const statusCode = response.statusCode;
        const message = `${method} ${url} ${statusCode} +${duration}ms requestId=${requestId} user=${userId} ip=${ip}`;
        if (statusCode >= 500) {
          this.logger.error(message);
          return;
        }
        if (statusCode >= 400) {
          this.logger.warn(message);
          return;
        }
        this.logger.log(message);
      }),
    );
  }
}
