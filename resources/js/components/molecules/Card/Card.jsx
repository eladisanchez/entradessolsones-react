import styles from "./Card.module.scss";
import classNames from "classnames";
import { margins } from "@/components/helpers";

const Card = ({ children, spacerTop, spacerBottom, className, hasTabs }) => {
  const classes = classNames(styles.card, className, {
    [margins[`mt${spacerTop}`]]: spacerTop,
    [margins[`mb${spacerBottom}`]]: spacerBottom,
    [styles.hasTabs]: hasTabs,
  });
  return <div className={classes}>{children}</div>;
};

export default Card;
