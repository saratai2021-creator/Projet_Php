import { useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { useNavigate, useParams } from "react-router-dom"
import { Ajout , Modifier } from "../store/sliceFilms";

export function AjoutModifier(){
        const dispatch=useDispatch();
        const navigate=useNavigate();
    const [nvFil,setNvfil]=useState({
        Id: Date.now().toString(),
      Titre: "",
      Réalisateur: "",
      Durée: 0,
      Poster: ""
    })

    

    const {Id}=useParams();
    const liste=useSelector(st=>st.films.listeFilms);

    const found=liste.find((fil)=>fil.Id===Id);
    
    const [Mdfil,setMdFil]=useState(found)
 function handleAjout(e){
    e.preventDefault();
    if(nvFil){
      dispatch(Ajout(nvFil));
      navigate("/")
    }
 }

  function handleModifier(e){
    e.preventDefault();
    if(Mdfil){
      dispatch(Modifier(Mdfil));
      navigate("/")
    }
 }
    if(!found){
        return(
            <form 
            onSubmit={handleAjout}
            className="form my-3">
            <h1>Ajoute un film</h1>
            <pre>
            <label>Titre : </label>

             <input 
             className=" w-75 my-3"
             value={nvFil.Titre}
             onChange={(e)=>
                setNvfil({
                
            ...nvFil,
            Titre:e.target.value
             })
             }
             ></input> 
             <br></br>
            <label>Réalisateur : </label>
             <input 
             className=" w-75"
             value={nvFil.Réalisateur}
             onChange={(e)=>
                setNvfil({
                
            ...nvFil,
            Réalisateur:e.target.value
             })
             }
             ></input><br></br>
            <label>Durée : </label>
             <input 
             className=" w-75"
             value={nvFil.Durée}
             onChange={(e)=>
                setNvfil({
                
            ...nvFil,
            Durée:e.target.value
             })
             }
             ></input><br></br>
            <label>Images : </label>
             <input 
             className=" w-75"
             value={nvFil.Poster}
             onChange={(e)=>
                setNvfil({
                
            ...nvFil,
            Poster:e.target.value
             })
             }
             ></input><br></br>
             <button
              type="submit"
            className="btn btn-primary"
             >Ajouter</button>
             </pre>
            </form>
            
        )
    }
    return(
          <form 
            onSubmit={handleModifier}
            className="form my-3">
            <h1>Modifier un Film</h1>
            <pre>
            <label>Titre : </label>
             <input 
             className=" w-75"
            value={Mdfil.Titre}
             onChange={(e)=>
                setMdFil({
                
            ...Mdfil,
            Titre:e.target.value
             })
             }
             
             ></input><br></br>
            <label> Réalisateur: </label>
             <input 
             className=" w-75"
              value={Mdfil.Réalisateur}
             onChange={(e)=>
                setMdFil({
                
            ...Mdfil,
            Réalisateur:e.target.value
             })
             }
             ></input> <br></br>
            <label>Durée: </label>
             <input 
             className=" w-75"
              value={Mdfil.Durée}
             onChange={(e)=>
                setMdFil({
                
            ...Mdfil,
            Durée:e.target.value
             })
             }
             ></input> <br></br>
            <label>Poster : </label>
             <input 
             className=" w-75"
              value={Mdfil.Poster}
             onChange={(e)=>
                setMdFil({
                
            ...Mdfil,
            Poster:e.target.value
             })
             }
             ></input> <br></br>
             <button
              type="submit"
            className="btn btn-info"
             >Modifier</button>
             </pre>
            </form>
    )
}