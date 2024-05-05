import styles from "./Footer.module.scss";
import { Container } from "@/components/atoms";

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
