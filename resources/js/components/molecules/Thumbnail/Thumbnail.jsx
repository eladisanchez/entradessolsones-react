import { Link } from "@inertiajs/react";
import styles from "./Thumbnail.module.scss";
import classNames from "classnames";

const Thumbnail = ({ product }) => {
  const classes = classNames(styles.thumbnail, {
    [styles.pack]: product.is_pack,
  });
  return (
    <div className={classes}>
      <Link href={"/activitat/" + product.name}>
        <img
          src={"https://source.unsplash.com/random/400x40" + (product.id % 9)}
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
