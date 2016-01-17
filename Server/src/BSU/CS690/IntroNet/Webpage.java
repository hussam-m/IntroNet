/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package BSU.CS690.IntroNet;

import java.io.IOException;
import java.io.PrintWriter;
import java.net.Socket;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author hussam
 */
public class Webpage {
    
    public Webpage(String path)
    {
        
    }
    
    static private String loadPage(Path fileName)
    {
        String page=null;
        try {
            byte[] encoded = Files.readAllBytes(fileName);
            page = new String(encoded, StandardCharsets.UTF_8);
        } catch (IOException ex) {
            Logger.getLogger(Webpage.class.getName()).log(Level.SEVERE, null, ex);
        }
        return page;
    }
    
    static public void sendWebPage(String path, Socket socket) throws IOException
    {
        PrintWriter out = new PrintWriter(socket.getOutputStream(), true);
        out.write(loadPage(Paths.get(path)));
        out.flush();
    }
}
