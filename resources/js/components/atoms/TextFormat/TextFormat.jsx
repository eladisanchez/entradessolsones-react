import classNames from "classnames";
import { colors, textalign } from "../../helpers";
import styles from "./TextFormat.module.scss";

const TextFormat = ({ color, textAlign, fontWeight, fontStyle, children, fontSize }) => {
  const classes = classNames({
    [colors[`text-${color}`]]: color,
    [textalign[`text-${textAlign}`]]: textAlign,
    [styles.block]: textAlign === "center" || textAlign === "right",
    [styles[`font-${fontWeight}`]]: fontWeight,
    [styles[`font-${fontStyle}`]]: fontStyle,
    [styles[`size-${fontSize}`]]: fontSize,
  });
  return <span className={classes}>{children}</span>;
};

export default TextFormat;
