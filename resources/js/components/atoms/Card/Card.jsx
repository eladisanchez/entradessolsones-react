import styles from "./Card.module.scss";
import classNames from "classnames";
import { margins } from "@/components/helpers";

const Card = ({ children, spacerTop, spacerBottom }) => {
  const classes = classNames(styles.card, {
    [margins[`mt${spacerTop}`]]: spacerTop,
    [margins[`mb${spacerBottom}`]]: spacerBottom,
  });
  return <div className={classes}>{children}</div>;
};

export default Card;
