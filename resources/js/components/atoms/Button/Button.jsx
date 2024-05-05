import { Link } from "@inertiajs/react";
import styles from "./Button.module.scss";
import classNames from "classnames";

const Button = ({
  children,
  props,
  size = "md",
  outline = false,
  block = false,
  onClick,
  disabled,
  link,
  href,
}) => {
  const classes = classNames(styles.button, {
    [styles.outline]: outline,
    [styles.lg]: size == "lg",
    [styles.block]: block,
    [styles.disabled]: disabled,
    [styles.link]: link,
  });
  if (href) {
    return (
      <Link
        href={href}
        preserveState
        preserveScroll
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
    >
      {children}
    </button>
  );
};

export default Button;
