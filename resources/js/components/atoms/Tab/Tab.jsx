import styles from "./Tab.module.scss";
import classNames from "classnames";

const Tab = ({
  children,
  props,
  onClick,
  selected
}) => {
  const classes = classNames(styles.tab, {
    [styles.selected]: selected,
  });
  return (
    <button
      className={classes}
      {...props}
      onClick={onClick}
      aria-label={children}
    >
      {children}
    </button>
  );
};

export default Tab;
