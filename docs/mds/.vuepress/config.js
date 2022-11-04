module.exports = {
    title: 'VuePoem - 一款轻量级，开源后台快速开发脚手架',
    description: '一款轻量级，开源后台快速开发脚手架。无过度封装概念，只要您同时会vue+php即可直接二次开发',
    themeConfig: {
        locales: {
            '/': {
                nav: [
                    { text: '首页', link: '/' },
                    { text: '演示', link: 'https://demo.vuepoem.easybhu.cn'},
                    { text: '文档', link: '/doc/' },
                    { text: "Github", link: 'https://github.com/fsd-lsh/vuepoem' },
                    { text: "Gitee", link: 'https://gitee.com/fsd-lsh/vuePoem' },
                    { text: "博客", link: 'https://www.easybhu.cn' },
                ],
                sidebar: {
                    '/doc/': ['dev&prod', 'guide', 'cliScript'],
                },
                displayAllHeaders: true,
            },
            '/doc/en/': {
                nav: [
                    { text: 'Home', link: '/' },
                    { text: 'Demo', link: 'https://demo.vuepoem.easybhu.cn'},
                    { text: 'Doc', link: '/doc/en/' },
                    { text: "Github", link: 'https://github.com/fsd-lsh/vuepoem' },
                    { text: "Gitee", link: 'https://gitee.com/fsd-lsh/vuePoem' },
                    { text: "Blog", link: 'https://www.easybhu.cn' },
                ],
                sidebar: {
                    '/doc/en/': ['dev&prod', 'guide', 'cliScript'],
                },
                displayAllHeaders: true,
            },
        },
    },
    locales: {
        '/': {
            lang: 'zh-CN',
            title: 'VuePoem',
            description: '一款轻量级，开源后台快速开发脚手架',
        },
        '/doc/en/': {
            lang: 'en-US',
            title: 'VuePress',
            description: 'A lightweight, open source background rapid development scaffolding',
        }
      },
    head: [
        ['script', {}, `
            var _hmt = _hmt || [];
            (function() {
              var hm = document.createElement("script");
              hm.src = "https://hm.baidu.com/hm.js?5cdee1a176dd8bfdc65da6805b834f78";
              var s = document.getElementsByTagName("script")[0]; 
              s.parentNode.insertBefore(hm, s);
            })();
        `]
    ],
    plugins: [
        ['autometa', {
                site: {
                    name: "VuePoem Document",
                },
                canonical_base: 'https://vuepoem.easybhu.cn',
            }
        ],
        ['sitemap', {
                hostname: "https://vuepoem.easybhu.cn",
                exclude: ["/404.html"]
            }
        ],
        [ 'feed', 'https://vuepoem.easybhu.cn']
    ],
}
