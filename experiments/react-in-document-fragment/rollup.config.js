import resolve from '@rollup/plugin-node-resolve';
import babel from 'rollup-plugin-babel';
import commonjs from 'rollup-plugin-commonjs';
import replace from '@rollup/plugin-replace';

export default [{
    input: 'src/index.js',
    output: {
        file: 'dist/js/main.js',
        format: 'umd'
    },
    plugins: [
        resolve(),
        babel({ 
            exclude: 'node_modules/**',
            presets: ['@babel/env', '@babel/preset-react']
        }),
        commonjs(),
        replace({
            'process.env.NODE_ENV': '"production"'
        }),
    ]
}];