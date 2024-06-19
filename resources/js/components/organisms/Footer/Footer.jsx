import styles from "./Footer.module.scss";
import { Container, Grid, TextFormat } from "@/components/atoms";
import { Link } from "@inertiajs/react";

const Footer = () => {
  return (
    <>
      <footer className={styles.footer}>
        <Container>
          <Grid>
            <div>
              <img
                src="/assets/img/logo-turisme.png"
                alt="Turisme Solsonès"
                className={styles.logo}
                width="150"
                height="60"
              />
            </div>
            <div>
              <p>
                  <strong>Oficina de Turisme del Solsonès</strong>
                  <br />
                  Ctra. de Bassella, 1<br />
                  25280 Solsona
                  <br />
                  Tel. 973 48 23 10
              </p>
            </div>
            <div>
              <Link href="">Com puc vendre entrades?</Link>
              <br />
              <Link href="">Condicions d'ús</Link>
              <br />
              <Link href="">Protecció de dades</Link>
              <br />
              <Link href="">Avís legal</Link>
              <br />
              <Link href="">Contacte</Link>
            </div>
          </Grid>
        </Container>
      </footer>
    </>
  );
};

export default Footer;
