import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import Layout from "./Layout";
import { locale, addLocale } from "primereact/api";
import { calendar } from "@/lang/ca";

addLocale("ca", calendar);
locale("ca");

createInertiaApp({
  progress: {
    color: '#007fb6'
  },
  resolve: (name) => {
    const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
    let page = pages[`./Pages/${name}.jsx`];
    page.default.layout =
      page.default.layout || ((page) => <Layout children={page} />);
    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />);
  },
});
