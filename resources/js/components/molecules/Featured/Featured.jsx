import styles from "./Featured.module.scss";
import { Container } from "@/components/atoms";

const Featured = () => {
  return (
    <div className={styles.featured}>
      <Container>
        <h2>Featured</h2>
      </Container>
    </div>
  );
};

export default Featured;
