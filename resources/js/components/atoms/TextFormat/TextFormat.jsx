import classNames from "classnames";
import { colors, textalign } from "../../helpers";
import styles from "./TextFormat.module.scss";

const TextFormat = ({ color, textAlign, fontWeight, fontStyle, children }) => {
  const classes = classNames({
    [colors[`text-${color}`]]: color,
    [textalign[`text-${textAlign}`]]: textAlign,
    [styles.block]: textAlign === "center" || textAlign === "right",
    [styles[`font-${fontWeight}`]]: fontWeight,
    [styles[`font-${fontStyle}`]]: fontStyle,
  });
  return <span className={classes}>{children}</span>;
};

export default TextFormat;
