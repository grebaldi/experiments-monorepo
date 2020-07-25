import resolve from '@rollup/plugin-node-resolve';
import commonjs from 'rollup-plugin-commonjs';
import {terser} from 'rollup-plugin-terser';
import replace from '@rollup/plugin-replace';

export default [{
    input: 'src/plugin.js',
    output: {
        file: 'dist/js/plugin.js',
        format: 'amd',
        amd: {
            define: `window['@Sitegeist.Monocle:PluginContainer']('MyPlugin', '1.0.0').register`
        }
    },
    plugins: [
        resolve(),
        commonjs(),
        replace({
            'process.env.NODE_ENV': '"production"'
        })
    ],
    external: ['react']
}, {
    input: 'src/host.js',
    output: {
        file: 'dist/js/host.js',
        format: 'umd'
    },
    plugins: [
        resolve(),
        commonjs(),
        replace({
            'process.env.NODE_ENV': '"production"'
        })
    ]
}];