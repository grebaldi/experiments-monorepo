## Nutzung der offiziellen ElasticSearch PHP API

https://github.com/elastic/elasticsearch-php

* Keep up-to-date with latest ElasticSearch development

## Explicit / Smaller Mapping

```yaml
PackageFactory:
  ElasticSearch:
    defaultPrefix: mysite-live
    indices:
      fulltext: resource://Vendor.MySite/Private/ElasticSearch/fulltext.yaml
```

* It is highly unlikely, that you want to override specific configuration values of your index configuration in your Settings.yaml, plus: doing that would also lead to obscure behavior of different environments

## Indexing mit Fusion



## Elastic Search GUI im Neos Backend