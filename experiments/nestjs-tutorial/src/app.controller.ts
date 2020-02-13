import { Controller, Get } from '@nestjs/common';
import { AppService } from './app.service';

import { createElement } from 'react';
import { renderToString } from 'react-dom/server';

@Controller()
export class AppController {
	constructor(private readonly appService: AppService) {}

	@Get()
	async getHello(): Promise<string> {
		const name = 'Test';
		const component = require(`./${name}`).default;

		return `
			<!doctype html>
			<html>
				<head>
					<title>First NestJS App</title>
				</head>
				<body>
					${renderToString(createElement(component, { title: 'Test' }))}
				</body>
			</html>
		`;
	}
}
