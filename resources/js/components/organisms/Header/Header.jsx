import { useState } from "react";
import { Container, Flex, Icon, Logo, Spacer } from "@/components/atoms";
import { SearchForm, CartIcon } from "@/components/molecules";
import { Link } from "@inertiajs/react";
import classNames from "classnames";
import styles from "./Header.module.scss";

const Header = ({ url }) => {
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
            {!isCheckout ? (
              <Flex alignItems="center" gap={1}>
                <div className={styles.search}>
                  <SearchForm />
                </div>
                <button
                  aria-label="Menú"
                  className={styles.menuButton}
                  onClick={handleToggleMenu}
                >
                  <Icon icon="menu" />
                </button>
                <CartIcon />
              </Flex>
            ) : (
              <button
                aria-label="Menú"
                className={styles.menuButton}
                onClick={handleToggleMenu}
              >
                <Icon icon="menu" />
              </button>
            )}
          </div>
          {isHome && (
            <div className={styles.headerContentSm}>
              <SearchForm />
            </div>
          )}
        </Container>
      </header>
      <nav className={menuClasses}>
        <Spacer top={2} bottom={2}>
        <Flex gap="2" flexDirection="column">
          <Link href="/#activitats">Activitats turístiques</Link>
          <Link href="/#activitats">Espectacles i esdeveniments</Link>
          <Link href="/calendari">Calendari</Link>
          <Link href="/ca/">Com puc vendre entrades?</Link>
          <Link href="/ca/">Turisme Solsonès</Link>
        </Flex>
        </Spacer>
        <Spacer top={2}>
          <SearchForm />
        </Spacer>
      </nav>
    </>
  );
};

export default Header;
