import { usePage } from "@inertiajs/react";
import { Header, Footer, Cart } from "@/components";
import { CartProvider } from "@/contexts/CartContext";

export default function Layout({ children }) {
  const { url, props } = usePage();
  const { cart, csrf_token } = props;
  return (
    <CartProvider initialCart={cart} csrf={csrf_token}>
      <main>
        <Header url={url}></Header>
        <Cart />
        <article>{children}</article>
        <Footer />
      </main>
    </CartProvider>
  );
}
