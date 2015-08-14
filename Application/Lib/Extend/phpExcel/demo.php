    <?php
    $path = APP_PATH . '/uploadfile/demoreport2.xls';
        $save_path = APP_PATH . '/uploadfile/demoreportsave.xls';
        // 导入PHPExcel类库  
        import("@/Extend/phpExcel/PHPExcel", '', '.php');
// 通常PHPExcel对象有两种实例化的方式
// 1. 通过new关键字创建空白文档
// $phpexcel = new PHPExcel();
// 2. 通过读取已有的模板创建
        $phpexcel = PHPExcel_IOFactory::createReader("Excel5")->load($path);
        /**
         * 实例化之后的PHPExcel对象类似于一个暂存于内存中文档文件， 
         * 可以对它进行操作以达到修改文档数据的目的
         */
// 设置文档属性
        $phpexcel->getProperties()->setCreator("Liu Jian") // 文档作者
                ->setLastModifiedBy("Liu Jian") // 最后一次修改者
                ->setTitle("Office 2003 XLS Test Document") // 标题
                ->setSubject("Office 2003 XLS Test Document") // 主题
                ->setDescription("Test document for Office 2003 XLS, generated using PHPExcel.") // 备注
                ->setKeywords("office 2003 openxml php") // 关键字
                ->setCategory("Test result file"); // 类别
// 默认状态下，新创建的空白文档（通过new）只有一个工作表（sheet），且它的编号（index）为0
// 可以通过如下的方式添加新的工作表
       // $phpexcel->createSheet(1);
// 获取已有编号的工作表
        $sheet = $phpexcel->getSheet(0);
// 设置当前激活的工作表编号
        $phpexcel->setActiveSheetIndex(0);
// 获取当前激活的工作表
        $sheet = $phpexcel->getActiveSheet();
        
// 得到工作表之后就可以操作它的单元格以修改数据了
// 修改工作表的名称
        $sheet->setTitle("Test");
// 设置单元格A5的值
        $sheet->setCellValue("E5", '不是于大111');
        $sheet->setCellValue("A3", '不是于大111');
        $sheet->setCellValue("H3", '不是于大111');
       // dump($sheet);
// 设置第3行第5列（E3）的值
       // $sheet->setCellValueByColumnAndRow(4, 3, date('Y-m-d h:i:s'));

// 获取单元格A5的值
       // $sheet->getCell("A5")->getValue();

// 合并单元格
       // $sheet->mergeCells("C3:G6");

// 拆分合并的单元格
       // $sheet->unmergeCells("C3:G6");

// 设置第3行的属性
//        $sheet->getRowDimension(3)->setRowHeight(100) // 行高
//                ->setVisible(true) // 是否可见，默认为true 
//                ->setRowIndex(6) // 变更行号为6
//                ->setOutlineLevel(5); // 优先级别，默认为0，参数必须是0到7
// 设置第F列的属性
// getColumnDimension("F")可以用getColumnDimensionByColumn(5)代替
//        $sheet->getColumnDimension("F")->setWidth(200) // 列宽
//                ->setColumnIndex("I") // 变更列号为I
//                ->setVisible(false) // 是否可见
//                ->setAutoSize(true); // 自动适应列宽
// 在第3行前面插入1行，该行将变成新的第3行，其它的依次下移1行
//        $sheet->insertNewRowBefore(3, 1);
//
//// 在第C行前面插入1列，该列将变成新的第C列，其它的依次右移1列
//        $sheet->insertNewColumnBefore("C", 1); // 方法一
//        $sheet->insertNewColumnBeforeByIndex(2, 1); // 方法二，第C列又是第2列
//// 获取单元格D3的样式对象
//        $style = $sheet->getStyle("D3"); // 等价于getStyleByColumnAndRow(3, 3)
//// 设置该单元格的字体属性
//        $style->getFont()->setBold(true) // 是否粗体
//                ->setSize(16) // 字号
//                ->setName("Gungsuh") // 字体名，只适用于外文字体
//                ->setItalic(true) // 是否斜体
//                ->setStrikethrough(true) // 是否有删除线
//                ->setUnderline(PHPExcel_Style_Font::UNDERLINE_DOUBLEACCOUNTING) // 下划线类型
//                ->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE); // 字体颜色
//// 设置该单元格的背景填充属性
//        $style->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID) // 填充模式
//                ->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW); // 背景颜色
//// 设置该单元格中数字的格式
//        $style->getNumberFormat()->setFormatCode("0.00");
//
//// 设置该单元格中文本对齐方式
//        $style->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER) // 水平方向
//                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // 垂直方向
//
//        $sheet->setCellValue("D3", "12.3456");

// 在本地保存文档
        PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5')->save($save_path);
//
//// 输出文档到页面
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="output.xls"');
//        header('Cache-Control: max-age=0');
//        PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5')->save('php://output');
//      

 ?>