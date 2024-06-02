import styles from "./SearchForm.module.scss";
import classNames from "classnames";
import { Input } from "@/components/atoms";
import { router } from "@inertiajs/react";
import { useState } from "react";

const SearchForm = () => {
  const [prevPage, setPrevPage] = useState(null);
  const [search, setSearch] = useState("");
  const handleSearch = (e) => {
    const currentPage = window.location.pathname;
    const newSearch = e.target.value;
    if (newSearch.length > 2) {
      setSearch(newSearch);
    }
    if (search.length > 2) {
      setPrevPage(currentPage);
      router.get("/search?s=" + search);
    } else {
      if (prevPage) {
        router.get(prevPage);
      }
    }
  };
  return (
    <>
      <Input
        onChange={handleSearch}
        placeholder="Cerca activitats o esdeveniments"
        className={styles.search}
      ></Input>
    </>
  );
};

export default SearchForm;
