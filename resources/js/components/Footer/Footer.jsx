import styles from "./Footer.module.scss";
import { Container } from "@/components/ui";

const Footer = () => {
  return (
    <>
      <footer className={styles.footer}>
        <Container></Container>
      </footer>
    </>
  );
};

export default Footer;
