import styles from "./Container.module.scss";
import classNames from "classnames";

const Container = ({ children, className, props }) => {
  const classes = classNames(styles.container, className);
  return (
    <div className={classes} {...props}>
      {children}
    </div>
  );
};

export default Container;
