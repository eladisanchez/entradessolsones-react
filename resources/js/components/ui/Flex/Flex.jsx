import styles from "./Flex.module.scss";
import classNames from "classnames";

const Flex = ({ flexDirection, justifyContent, alignItems, gap, children }) => {
  const classes = classNames(styles.flex, {
    [styles[`justify-${justifyContent}`]]: justifyContent,
    [styles[`align-${alignItems}`]]: alignItems,
    [styles[`flex-${flexDirection}`]]: flexDirection,
  });
  return (
    <div className={classes} style={{ gap: `${gap * 8}px` }}>
      {children}
    </div>
  );
};

export default Flex;
