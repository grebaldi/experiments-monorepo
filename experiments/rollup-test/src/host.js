import React from "react";

window['@Sitegeist.Monocle:PluginContainer'] = (pluginName, version) => {
    return { register: makeRegister(pluginName) };
}

const dependencies = { react: React };
const plugins = {};

const makeRegister = pluginName => async function register(deps, makeInitialize) {
    if (pluginName in plugins) {
        const { resolve, reject } = plugins[pluginName];

        try {
            const resolvedDependencies = await Promise.all(deps.map(dep => dependencies[dep]));
            const initialize = makeInitialize(...resolvedDependencies);
        
            await initialize();
            resolve();
        } catch (err) {
            reject(err);
        }
    }
}

function load(pluginName, src) {
    const script = document.createElement('script');
    script.src = src;

    document.head.appendChild(script);

    return new Promise((resolve, reject) => {
        const timeout = setTimeout(() => {
            delete plugins[pluginName];
            reject();
        }, 10000);

        plugins[pluginName] = {
            resolve: () => {
                document.head.removeChild(script);
                delete plugins[pluginName];
                clearTimeout(timeout);
                resolve();
            }, 
            reject: reason => {
                document.head.removeChild(script);
                delete plugins[pluginName];
                clearTimeout(timeout);
                reject(reason);
            }
        };
    });
}

async function main() {
    await load('MyPlugin', '/js/plugin.js');
    console.log('Plugin loaded!', plugins);
}

main();