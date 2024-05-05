import styles from "./Heading.module.scss";
import { colors, margins } from "@/components/helpers";
import classNames from "classnames";

const Heading = ({
  tag,
  spacerTop,
  spacerBottom,
  size = 1,
  center = false,
  props,
  color = null,
  children,
}) => {
  const classes = classNames(
    styles.heading,
    styles["h" + size],
    color ? colors["text-" + color] : "",
    {
      [margins[`mt${spacerTop}`]]: spacerTop,
      [margins[`mb${spacerBottom}`]]: spacerBottom,
      [styles.center]: center,
    }
  );
  const Tag = tag || "h1";
  return (
    <Tag className={classes} {...props}>
      {children}
    </Tag>
  );
};

export default Heading;
