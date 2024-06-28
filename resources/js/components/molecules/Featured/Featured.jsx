import styles from "./Featured.module.scss";
import { Container, Flex } from "@/components/atoms";
import { usePage } from "@inertiajs/react";
import { Link } from "@inertiajs/react";

const Item = ({ product }) => {
  return (
    <div className={styles.item}>
      <Link href={"/activitat/" + product.name}>
        <Flex gap={2} alignItems="center">
          <img
            src={"/image/" + product.image}
            alt={product.title}
            className={styles.thumbnail}
          />
          <div className={styles.overlay}>
            <h3>{product.title}</h3>
            <p>Del 2 al 18 de juliol</p>
          </div>
        </Flex>
      </Link>
    </div>
  );
};

const Featured = () => {
  const { props } = usePage();
  const { featured } = props;
  return (
    <div className={styles.featured}>
      <Container>
        <Flex gap={2} justifyContent="center" className={styles.featuredGrid}>
          {featured.map((item, index) => (
            <Item key={index} product={item} />
          ))}
        </Flex>
      </Container>
    </div>
  );
};

export default Featured;
