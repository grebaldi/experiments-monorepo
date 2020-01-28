import { NextApiRequest, NextApiResponse } from "next";
import { readFile, exists } from "fs";
import { safeLoadAll } from "js-yaml";
import { Observable, Subscriber } from "rxjs";
import { toArray } from "rxjs/operators";

export default async function (req: NextApiRequest, res: NextApiResponse) {
    const data$ = new Observable((observer: Subscriber<any>) => {
        exists('data/data.yaml', exists => {
            if (exists) {
                readFile('data.yaml', (err, buffer) => {
                    if (err) {
                        observer.error(err);
                    } else {
                        try {
                            safeLoadAll(String(buffer), function (doc) {
                                observer.next(doc);
                            }, { onWarning: warning => observer.error(warning) });
                        } catch(err) {
                            observer.error(err);
                        }
        
                        observer.complete();
                    }
                });
            } else {
                observer.complete();
            }
        });
    });

    try {
        const data = await data$.pipe(toArray()).toPromise();
        res.json(data)
    } catch (err) {
        res.json({err})
    }
}
