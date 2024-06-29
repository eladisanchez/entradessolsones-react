import styles from "./Footer.module.scss";
import { Container, Grid, Flex } from "@/components/atoms";
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
              <Flex gap={1} flexDirection="column">
                <Link href="/organitzadors">Com puc vendre entrades?</Link>
                <Link href="/condicions">Condicions d'ús</Link>
                <Link href="/politica-privacitat">Política de privacitat</Link>
                <a
                  href="https://turismesolsones.com/avis-legal"
                  target="_blank"
                >
                  Avís legal
                </a>
                <a href="https://turismesolsones.com/contacte" target="_blank">
                  Contacte
                </a>
              </Flex>
            </div>
          </Grid>
        </Container>
      </footer>
    </>
  );
};

export default Footer;
