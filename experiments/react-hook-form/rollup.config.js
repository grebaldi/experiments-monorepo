import replace from '@rollup/plugin-replace';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import babel from 'rollup-plugin-babel';

const extensions = ['.js', '.ts', '.tsx'];

export default {
	input: 'src/index.tsx',
	output: {
		file: 'dist/index.js',
		format: 'iife',
	},
	plugins: [
		replace({
			'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV),
		}),
		resolve({
			extensions,
		}),
		commonjs({
            include: /node_modules/,
		}),
		babel({
			extensions,
			exclude: /node_modules/,
		}),
	],
};
