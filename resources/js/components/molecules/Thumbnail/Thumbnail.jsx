import { Link } from "@inertiajs/react";
import styles from "./Thumbnail.module.scss";
import classNames from "classnames";
import { LazyLoadImage } from "react-lazy-load-image-component";
import 'react-lazy-load-image-component/src/effects/blur.css';

const Thumbnail = ({ product }) => {
  const classes = classNames(styles.thumbnail, {
    [styles.pack]: product.is_pack,
  });
  return (
    <div className={classes}>
      <Link href={"/activitat/" + product.name}>
        <LazyLoadImage
          src={'/image/' + product.image}
          width="100%"
          height="100%"
          effect="blur"
          alt={product.title}
        />
        <div className={styles.content}>
          <h3>{product.title}</h3>
          {product.summary && <p>{product.summary}</p>}
        </div>
      </Link>
    </div>
  );
};

export default Thumbnail;
