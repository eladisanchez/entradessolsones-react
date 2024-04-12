import { Link } from "@inertiajs/react";
import styles from "./Header.module.scss";
import { Container, Logo, Icon } from "@/components/ui";
import classNames from "classnames";
import { useCart } from "@/contexts/CartContext";

const Header = ({ url }) => {
  const { toggleCart, count } = useCart();
  const isHome =
    url === "/" || url.startsWith("/#") || url.startsWith("/calendari");
  const isCheckout = url.startsWith("/confirmacio");
  const classes = classNames(styles.header, {
    [styles.homeheader]: isHome,
  });

  console.log('count:',count)
  return (
    <>
      {!isCheckout && (
        <div className={styles.top}>
          <Container>
            <div className={styles.langnav}>
              <Link href="/ca/">CA</Link>
              <Link href="/es/">ES</Link>
            </div>
            <div className={styles.topnav}>
              <Link href="/ca/">Calendari</Link>
              <Link href="/ca/">Com puc vendre entrades?</Link>
              <Link href="/ca/">Turisme Solsonès</Link>
            </div>
          </Container>
        </div>
      )}
      <header className={classes}>
        <Container>
          <div className={styles.headerContent}>
            <div className={styles.logo}>
              <Link href="/">
                <Logo />
              </Link>
            </div>
            {!isCheckout && (
              <div>
                <button
                  onClick={() => toggleCart()}
                  className={styles.cartButton}
                >
                  <Icon icon="cart" />
                  {!!count && (
                    <span className={styles.cartQty}>{count}</span>
                  )}
                </button>
              </div>
            )}
          </div>
        </Container>
      </header>
    </>
  );
};

export default Header;
