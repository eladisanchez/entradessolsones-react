import { Container, Flex, Icon, Logo } from "@/components/atoms";
import { SearchForm } from "@/components/molecules";
import { useCart } from "@/contexts/CartContext";
import { Link } from "@inertiajs/react";
import classNames from "classnames";
import styles from "./Header.module.scss";

const Header = ({ url }) => {
  const { toggleCart, count } = useCart();
  const isHome =
    url === "/" || url.startsWith("/#") || url.startsWith("/calendari");
  const isCheckout = url.startsWith("/confirmacio");
  const classes = classNames(styles.header, {
    [styles.homeheader]: isHome,
  });
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
              <Link href="/" ariaLabel="Entrades Solsonès">
                <Logo />
              </Link>
            </div>
            {!isCheckout && (
              <Flex alignItems="center" gap={1}>
                <SearchForm />
                <button
                  onClick={() => toggleCart()}
                  className={styles.cartButton}
                >
                  <Icon icon="cart" />
                  {!!count && (
                    <span className={styles.cartQty}>{count}</span>
                  )}
                </button>
              </Flex>
            )}
          </div>
        </Container>
      </header>
    </>
  );
};

export default Header;
