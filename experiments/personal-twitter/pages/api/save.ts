import { NextApiRequest, NextApiResponse } from "next";
import { createWriteStream } from "fs";
import { safeDump } from "js-yaml";

const stream = createWriteStream('data/data.yaml', { flags: 'a' });

export default async function (req: NextApiRequest, res: NextApiResponse) {
    const action = JSON.parse(req.body);
    const yaml = safeDump(action);

    return new Promise((resolve, reject) => {
        stream.write('---\n' + yaml, (err) => {
            if (err) {
                reject(res.json({ err }));
            } else {
                resolve(res.json({ status: 'ok' }));
            }
        })
    });
}