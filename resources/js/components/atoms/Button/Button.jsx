import { Link } from "@inertiajs/react";
import styles from "./Button.module.scss";
import classNames from "classnames";

const Button = ({
  children,
  props,
  size = "md",
  outline = false,
  block = false,
  color = "primary",
  onClick,
  disabled,
  noWrap,
  link,
  href, 
  preserveScroll,
  preserveState,
  only
}) => {
  const classes = classNames(styles.button, {
    [styles.outline]: outline,
    [styles['color-'+color]]: color,
    [styles.lg]: size == "lg",
    [styles.sm]: size == "sm",
    [styles.block]: block,
    [styles.disabled]: disabled,
    [styles.nowrap]: noWrap,
    [styles.link]: link,
  });
  if (href) {
    return (
      <Link
        href={href}
        preserveState={preserveState}
        preserveScroll={preserveScroll}
        only={only}
        className={classes}
        {...props}
        disabled={disabled}
      >
        {children}
      </Link>
    );
  }
  return (
    <button
      className={classes}
      {...props}
      onClick={onClick}
      disabled={disabled}
      aria-label={children}
    >
      {children}
    </button>
  );
};

export default Button;
