import styles from "./Spacer.module.scss";
import classNames from "classnames";

const Spacer = ({ top, bottom, children, className, ...props }) => {
  const classes = classNames(className, {
    [styles[`spacer-top-${top}`]]: top,
    [styles[`spacer-bottom-${bottom}`]]: bottom,
  });
  return <div className={classes} {...props}>{children}</div>;
};

export default Spacer;
