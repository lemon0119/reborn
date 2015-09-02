using System;
using System.IO;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Microsoft.Office.Core;
using Microsoft.Office.Interop.PowerPoint;

namespace ppt2jpg
{
    class Program
    {
        static void Main(string[] args)
        {
            // 显示Logo
            Version();

            // 如果不带参数，输出帮助信息
            if (args.Length == 0)
            {
                Help();
                Environment.Exit(9);
                return;
            }

            // 判断第1个参数是否-v或-h，如果是，输出相应的信息
            switch (args[0].ToLower().Substring(0, 2))
            {
                case "-v":
                    Environment.Exit(0);
                    return;
                case "-h":
                    Help();
                    Environment.Exit(0);
                    return;
            }

            // 解析文件名
            string pptFilename = null;
            string jpgFilename = null;
            try
            {
                pptFilename = Path.GetFullPath(args[0]);
                if (args.Length > 1) { jpgFilename = Path.GetFullPath(args[1]); }
            }
            catch (Exception e)
            {
                Console.WriteLine("参数中包含不正确的文件名");
                Environment.Exit(2);
                return;
            }

            // 判断输入文件是否存在
            if (!File.Exists(pptFilename))
            {
                Console.WriteLine("错误：指定文件不存在");
                Environment.Exit(1);
                return;
            }

            if (jpgFilename == null)
            {
                jpgFilename = Path.ChangeExtension(pptFilename, "jpg");
            }

            Console.WriteLine(string.Format(@"正在转换 [{0}]
      -> [{1}]", pptFilename, jpgFilename));
            ApplicationClass pptApplication = new ApplicationClass();
            Presentation pptPresentation = pptApplication.Presentations.Open(pptFilename, MsoTriState.msoFalse, MsoTriState.msoFalse, MsoTriState.msoFalse);
            pptPresentation.SaveAs(jpgFilename, PpSaveAsFileType.ppSaveAsJPG);
            pptApplication.Quit();
        }

        static void Version()
        {
            Console.WriteLine(@"ppt2jpg - 将PPT转换为JPG
Copyright (c) 2015 wangfei
版本：1.0 (OFFICE 2007)
");
        }

        static void Help()
        {
            Console.WriteLine(@"
命令：ppt2jpg OFFICE文件 [PPT文件]
      将指定的PPT文件转换为JPG文件，若未指定JPG文件，
      生成的JPG文件与PPT文件同名，且扩展名改为JPG。
");
        }

    }
}