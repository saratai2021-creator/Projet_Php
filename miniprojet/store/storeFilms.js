import FilmsSlice from "./sliceFilms.js";
import { configureStore } from "@reduxjs/toolkit";
export const store=configureStore({
    reducer:{
        films:FilmsSlice
    }
})