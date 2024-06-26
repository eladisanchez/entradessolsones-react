import { usePage } from "@inertiajs/react";
import { BackToTop } from "@/components/molecules";
import { Header, Footer, SideCart } from "@/components/organisms";
import { CartProvider } from "@/contexts/CartContext";

export default function Layout({ children }) {
  const { url, props } = usePage();
  const { cart, csrf_token } = props;
  return (
    <CartProvider initialCart={cart} csrf={csrf_token}>
      <main>
        <Header url={url}></Header>
        <SideCart />
        <div className="site-main">{children}</div>
        <BackToTop />
        <Footer />
      </main>
    </CartProvider>
  );
}
