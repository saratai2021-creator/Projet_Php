import { useEffect } from "react";

import { getFilms } from "../store/sliceFilms.js"
import { useDispatch , useSelector } from "react-redux";
export function ChargeApi(){
    const {loading , error }=useSelector(st=>st.films);
const dispatch=useDispatch();
const liste = useSelector(st =>st.films.apiFilm);

useEffect(()=>{
dispatch(getFilms())
},[dispatch])
if(loading) return <p
className="text-center fw-bolder fs-3 text-warning"
>...Changement</p>
    return(
        <>
   <table className="table my-2 mx-2">
    <thead>
        <tr>
            <th>
                Id Film
            </th>
            <th>
                Titre Film
            </th>
            <th>
                Réalisateur  Film
            </th>
            <th>
                Durée de  Film
            </th>
            <th>
                Poster Film
            </th>
        </tr>
    </thead>
    <tbody>
         { liste.map((film)=>
 <tr 
 key={film.Id}
 className="">
 <td>
    {film.Id}
 </td>
  <td>
    {film.Titre}
 </td>
  <td>
    {film.Réalisateur}
 </td>
  <td>
    {film.Durée}
 </td>
  <td>
    <img
    alt=""
    src= {film.Poster}
    width="100px"
    >
    </img>
  
 </td>
</tr>
)}
 
    </tbody>
   </table>
    {error && <p
className="text-center fw-bolder fs-3 text-danger"
>{error}</p>} </>
    )
}