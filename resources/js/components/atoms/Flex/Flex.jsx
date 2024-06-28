import styles from "./Flex.module.scss";
import classNames from "classnames";
import { margins } from "@/components/helpers";

const Flex = ({
  flexDirection,
  justifyContent,
  alignItems,
  gap,
  children,
  spacerTop,
  spacerBottom,
  className,
  ...props
}) => {
  const classes = classNames(styles.flex, className, {
    [styles[`justify-${justifyContent}`]]: justifyContent,
    [styles[`align-${alignItems}`]]: alignItems,
    [styles[`flex-${flexDirection}`]]: flexDirection,
    [margins[`mt${spacerTop}`]]: spacerTop,
    [margins[`mb${spacerBottom}`]]: spacerBottom,
  });
  return (
    <div className={classes} style={{ gap: `${gap * 8}px` }} {...props}>
      {children}
    </div>
  );
};

export default Flex;
