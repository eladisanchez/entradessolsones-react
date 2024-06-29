import styles from "./HomeNav.module.scss";
import { Icon } from "@/components/atoms";
import { Link } from "@inertiajs/react";
import classNames from "classnames";

const HomeNav = ({ view = "list", activeSection }) => {
  return (
    <div className={styles.homenav}>
      <div className={styles.nav}>
        <Link
          href="/#activitats"
          className={activeSection == "activities" ? styles.currentSection : ""}
        >
          <span className={styles.titleDesktop}>Activitats turístiques</span>
          <span className={styles.titleMobile}>Turisme</span>
        </Link>
        <Link
          href="/#esdeveniments"
          className={activeSection == "events" ? styles.currentSection : ""}
        >
          <span className={styles.titleDesktop}>
            Teatre, concerts i esdeveniments
          </span>
          <span className={styles.titleMobile}>Esdeveniments</span>
        </Link>
      </div>
      <div className={styles.buttons}>
        <Link
          href="/"
          className={classNames(styles.button_list, {
            [styles.current]: view == "list",
          })}
          aria-label="Llista de productes"
        >
          <Icon icon="grid" />
        </Link>
        <Link
          href="/calendari"
          className={classNames(styles.button_calendar, {
            [styles.current]: view == "calendar",
          })}
          aria-label="Calendari"
        >
          <Icon icon="calendar" />
        </Link>
      </div>
    </div>
  );
};

export default HomeNav;
