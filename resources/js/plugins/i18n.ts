import { createI18n, type I18nOptions } from "vue-i18n";
import localeMessages from "@/app/locales/index";
import { App } from "vue";

const legacy: boolean = false;
const globalInjection: boolean = true;
const locale: string = "en";
const fallbackLocale: string = "en";

export default createI18n({
  legacy,
  globalInjection,
  locale,
  fallbackLocale,
  messages: localeMessages,
  allowComposition: true,
}) as { install: (app: App<I18nOptions>) => any };
