import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';

import { createElement } from "react";
import { renderToString } from "react-dom/server";
import { getInitialProps } from "./Test";

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  @Get()
  async getHello(): Promise<string> {
    const { myFirstPath } = await getInitialProps();

    return `
      <!doctype html>
      <html>
        <head>
          <title>First NestJS App</title>
        </head>
        <body>
          ${renderToString(createElement(myFirstPath.component, myFirstPath.props))}
        </body>
      </html>
    `;
  }
}
