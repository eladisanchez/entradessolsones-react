import styles from "./Heading.module.scss";
import colors from "./Colors.module.scss";
import classNames from "classnames";

const Heading = ({
  tag,
  spacerTop = 4,
  spacerBottom = 4,
  size = 1,
  center = false,
  props,
  color = null,
  children,
}) => {
  const classes = classNames(
    styles.heading,
    styles["h" + size],
    styles["mt" + spacerTop],
    styles["mb" + spacerBottom],
    color ? colors["text-" + color] : "",
    {
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
