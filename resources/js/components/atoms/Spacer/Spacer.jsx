import styles from "./Spacer.module.scss";
import classNames from "classnames";

const Spacer = ({ top, bottom, children, className, background, ...props }) => {
  const classes = classNames(className, {
    [styles[`spacer-top-${top}`]]: top,
    [styles[`spacer-bottom-${bottom}`]]: bottom,
    [styles.bgLight]: background == 'light',
    [styles.bgWhite]: background == 'white',
  });
  return <div className={classes} {...props}>{children}</div>;
};

export default Spacer;
