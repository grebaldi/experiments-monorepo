```fusion
describe.'MyContentComponent' {
    @env.contentRepository {
        siteNode {
            nodeType = 'Vendor.Site:Homepage'
            properties {

            }
            children {
                0 = ${ref('#2235c485-964c-4a14-98e2-e08d9af9002a')}
            }
        }

        nodes {
            '2235c485-964c-4a14-98e2-e08d9af9002a' {

            }
        }
    }

    'it should render a teaser node correctly' {
        teaser = Vendor.Site:Content.Teaser {
            @context.node = Mock:Node {
                nodeType = 'Vendor.Site:Content.Teaser'
                properties {
                    title = 'Test'
                }
            }
        }

        @assert = ${
            expect(value.teaser).not.toBe(null) &&
            expect(value.teaser).toMatchSnapshot()
        }
    }

    'it should render a link if it has been set' {
        teaser = Vendor.Site:Content.Teaser {
            @context.node = Mock:Node {
                nodeType = 'Vendor.Site:Content.Teaser'
                properties {
                    title = 'Test'
                    link = 'http://example.com/page'
                }
            }
        }

        @assert = ${
            expect(value.teaser).toContain('<a href="http://example.com/page"')
        }
    }

    'it should not render a link if it has not been set' {
        teaser = Vendor.Site:Content.Teaser {
            @context.node = Mock:Node {
                nodeType = 'Vendor.Site:Content.Teaser'
                properties {
                    title = 'Test'
                }
            }
        }

        @assert = ${
            expect(value.teaser).not.toContain('a href')
        }
    }
}
```

## Tasks

- Create Mechanism for `@env.contentRepository`
- Create Mechanism for `expect(...)`
- Create Testrunner
- Create Reporter