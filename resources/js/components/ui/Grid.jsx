import styles from "./Grid.module.scss";
import classNames from "classnames";

const Grid = ({ children, props, columns = 5 }) => {
  const classes = classNames(styles.grid,{
    [styles.twoColumns]: columns == 2,
    [styles.productGrid]: columns == 5
  });
  return (
    <div className={classes} {...props}>
      {children}
    </div>
  );
};

export default Grid;
