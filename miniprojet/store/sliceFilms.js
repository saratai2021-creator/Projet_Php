import {initStat} from "../data.js";
import {createSlice} from "@reduxjs/toolkit";

import { createAsyncThunk } from "@reduxjs/toolkit";
import axios from "axios";

export const getFilms=createAsyncThunk("get/films",
    async()=>{
        const reponse= await axios.get("http://localhost:3001/films");
        return reponse.data
    }
)
const FilmsSlice=createSlice({
    name:"films",
    initialState:{
        listeFilms:initStat.dbFilms,
        loading:false,
         apiFilm: [],     
        error:""
    
    },
    reducers:{
        Ajout:(state,action)=>{
      state.listeFilms.push(action.payload)
        },
        Supprimer:(state,action)=>{
            state.listeFilms=state.listeFilms.filter((film)=>film.Id!==action.payload)
        },
        Modifier:(state,action)=>{
           const found=
           state.listeFilms.find((film)=>film.Id===action.payload.Id);
           if(found){
            found.Titre=action.payload.Titre;
            found.Réalisateur=action.payload.Réalisateur;
            found.Durée=action.payload.Durée;
            found.Poster=action.payload.Poster
           }
        }
    },
    extraReducers:(build)=>{
        build
        .addCase(getFilms.pending,(state)=>{
          state.loading=true;
          state.error=null
        })
        .addCase(getFilms.fulfilled,(state,action)=>{
          state.loading=false;
           state.apiFilm = action.payload;
          state.error=null
        })
        .addCase(getFilms.rejected,(state,action)=>{
          state.loading=false;
          
          state.error=action.error.message
        })
    

    }
})
export const {Ajout,Supprimer,Modifier}=FilmsSlice.actions
export default FilmsSlice.reducer;