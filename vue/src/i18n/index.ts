import { createI18n } from 'vue-i18n';
import zh from "../../../common/lang/zh.json";
import en from "../../../common/lang/en.json";

let lang = window.localStorage.getItem('sys-lang');
lang = lang ? lang : 'zh';
window.localStorage.setItem('sys-lang', lang);

const i18n = createI18n({
    globalInjection: true,
    locale: lang,
    messages: {
        'zh': zh,
        'en': en,
    },
    legacy: false,
});
export default i18n;
