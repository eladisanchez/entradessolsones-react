import styles from "./BackToTop.module.scss";
import classNames from "classnames";
import { useEffect, useState } from "react";

const BackToTop = () => {
  const [visible, setVisible] = useState(false);
  const handleBackToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  };

  useEffect(() => {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 100) {
        setVisible(true);
      } else {
        setVisible(false);
      }
    });
  }, []);

  const classes = classNames(styles.backToTop, {
    [styles.visible]: visible,
  });

  return (
    <div className={classes} onClick={handleBackToTop}>
      <svg
        width="800px"
        height="800px"
        viewBox="0 0 32 32"
        xmlns="http://www.w3.org/2000/svg"
      >
        <g id="chevron-top">
          <line class="cls-1" x1="16" x2="25" y1="11.5" y2="20.5" />
          <line class="cls-1" x1="7" x2="16" y1="20.5" y2="11.5" />
        </g>
      </svg>
    </div>
  );
};

export default BackToTop;
