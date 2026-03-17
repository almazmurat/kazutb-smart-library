import { NestFactory } from '@nestjs/core';
import { ValidationPipe, VersioningType } from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import helmet from 'helmet';

import { AppModule } from './app.module';
import { AllExceptionsFilter } from './common/filters/all-exceptions.filter';
import { LoggingInterceptor } from './common/interceptors/logging.interceptor';

async function bootstrap() {
  const app = await NestFactory.create(AppModule, {
    logger: ['error', 'warn', 'log', 'debug'],
  });

  const configService = app.get(ConfigService);
  const port = configService.get<number>('PORT', 3000);
  const nodeEnv = configService.get<string>('NODE_ENV', 'development');
  const frontendUrl = configService.get<string>('FRONTEND_URL', 'http://localhost:5173');

  // Security headers
  app.use(helmet());

  // CORS — restrict to known frontend origin
  app.enableCors({
    origin: frontendUrl,
    methods: ['GET', 'POST', 'PATCH', 'DELETE', 'OPTIONS'],
    allowedHeaders: ['Content-Type', 'Authorization'],
    credentials: true,
  });

  // Global API prefix
  app.setGlobalPrefix('api');

  // URI-based API versioning: /api/v1/...
  app.enableVersioning({ type: VersioningType.URI });

  // Global validation pipe — reject unknown fields, auto-transform types
  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      transform: true,
      forbidNonWhitelisted: true,
      transformOptions: { enableImplicitConversion: true },
    }),
  );

  // Global exception filter — standardized error response format
  app.useGlobalFilters(new AllExceptionsFilter());

  // Global logging interceptor — logs all incoming requests
  app.useGlobalInterceptors(new LoggingInterceptor());

  // Swagger / OpenAPI (development only)
  if (nodeEnv !== 'production') {
    const swaggerConfig = new DocumentBuilder()
      .setTitle('KazUTB Smart Library API')
      .setDescription('REST API for the KazUTB Digital Smart Library system')
      .setVersion('1.0')
      .addBearerAuth()
      .build();
    const document = SwaggerModule.createDocument(app, swaggerConfig);
    SwaggerModule.setup('api/docs', app, document);
  }

  await app.listen(port);
  console.log(`[Bootstrap] Application running on http://localhost:${port}/api`);
  if (nodeEnv !== 'production') {
    console.log(`[Bootstrap] Swagger docs at http://localhost:${port}/api/docs`);
  }
}

bootstrap();
