import { useState } from "react";
import { Container, Flex, Icon, Logo, Spacer } from "@/components/atoms";
import { SearchForm } from "@/components/molecules";
import { useCart } from "@/contexts/CartContext";
import { Link } from "@inertiajs/react";
import classNames from "classnames";
import styles from "./Header.module.scss";

const Header = ({ url }) => {
  const { toggleCart, count } = useCart();
  const isHome =
    url === "/" || url.includes("#") || url.startsWith("/calendari");
  const isCheckout = url.startsWith("/confirmacio");
  const classes = classNames(styles.header, {
    [styles.homeheader]: isHome,
    [styles.solid]: isCheckout,
  });
  const [menuOpen, setMenuOpen] = useState(false);
  const menuClasses = classNames(styles.mobileNav, {
    [styles.menuOpen]: menuOpen,
  });

  const handleToggleMenu = () => {
    setMenuOpen(!menuOpen);
  };
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
              <Link href="/calendari">Calendari</Link>
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
              <Link
                href="/"
                aria-label="Entrades Solsonès"
                title="Entrades Solsonès"
              >
                <Logo />
              </Link>
            </div>
            {!isCheckout && (
              <Flex alignItems="center" gap={1}>
                <SearchForm />
                <button
                  aria-label="Menú"
                  className={styles.menuButton}
                  onClick={handleToggleMenu}
                >
                  <Icon icon="menu" />
                </button>
                <button
                  aria-label="Cistell de la compra"
                  onClick={() => toggleCart()}
                  className={styles.cartButton}
                >
                  <Icon icon="cart" />
                  {!!count && <span className={styles.cartQty}>{count}</span>}
                </button>
              </Flex>
            )}
          </div>
        </Container>
      </header>
      <nav className={menuClasses}>
        <Flex gap="1" flexDirection="column">
          <Link href="/calendari">Calendari</Link>
          <Link href="/ca/">Com puc vendre entrades?</Link>
          <Link href="/ca/">Turisme Solsonès</Link>
        </Flex>
        <Spacer top={2}>
          <SearchForm />
        </Spacer>
      </nav>
    </>
  );
};

export default Header;
