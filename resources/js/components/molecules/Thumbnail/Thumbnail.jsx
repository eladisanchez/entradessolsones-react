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
        />
        <div className={styles.content}>
          <h4>{product.title}</h4>
          {product.summary && <p>{product.summary}</p>}
        </div>
      </Link>
    </div>
  );
};

export default Thumbnail;
