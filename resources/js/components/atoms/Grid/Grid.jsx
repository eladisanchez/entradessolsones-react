import styles from "./Grid.module.scss";
import classNames from "classnames";

const Grid = ({ children, props, columns = 5 }) => {
  const classes = classNames(styles.grid,{
    [styles.two]: columns == 2,
    [styles.three]: columns == 3,
    [styles.thumbs]: columns == 5,
    [styles.checkout]: columns == 'checkout'
  });
  return (
    <div className={classes} {...props}>
      {children}
    </div>
  );
};

export default Grid;
